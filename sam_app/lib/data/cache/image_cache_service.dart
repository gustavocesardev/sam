import 'dart:collection';
import 'dart:typed_data';
import 'package:http/http.dart' as http;

class ImageCacheService {
  final int _maxCacheSize = 25;
  final LinkedHashMap<String, Uint8List> _cache = LinkedHashMap();

  /// Busca os bytes da imagem a partir da URL.
  Future<Uint8List> fetchImageBytes(String url) async {
    
    if (_cache.containsKey(url)) {
      /// Move para o fim da lista (mais recentemente usado)
      final bytes = _cache.remove(url)!;
      _cache[url] = bytes;
      return bytes;
    }

    final response = await http.get(Uri.parse(url));
    if (response.statusCode == 200) {
      /// Remove o mais antigo, se exceder o limite
      if (_cache.length >= _maxCacheSize) {
        _cache.remove(_cache.keys.first);
      }

      _cache[url] = response.bodyBytes;
      return response.bodyBytes;
    } else {
      throw Exception('Erro ao carregar imagem');
    }
  }

  Uint8List? getCachedImageBytes(String url) {
    return _cache[url];
  }

  void removeFromCache(String url) {
    _cache.remove(url);
  }

  void clearCache() {
    _cache.clear();
  }
}