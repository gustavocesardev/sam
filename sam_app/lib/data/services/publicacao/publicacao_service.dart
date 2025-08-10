import 'package:http/http.dart' as http;
import 'package:sam_app/data/services/http_service.dart';
import 'package:sam_app/data/requests/publicacao_request.dart';

class PublicacaoService {
  final HttpService _http = HttpService();

  Future<void> criarPublicacao({
    required String chaveAutor,
    required PublicacaoRequest request,
  }) async {
    
    final fields = <String, String>{
      chaveAutor: request.idAutor.toString(),
      'texto': request.texto,
    };

    if (request.idPublicacaoVinculada != null) {
      fields['id_publicacao_vinculada'] = request.idPublicacaoVinculada.toString();
    }

    final List<http.MultipartFile> arquivos = [];

    if (request.imagens != null) {
      for (int i = 0; i < request.imagens!.length; i++) {
        final image = request.imagens![i];
        final fileName = image.path.split('/').last;
        arquivos.add(
          await http.MultipartFile.fromPath(
            'imagens[$i]',
            image.path,
            filename: fileName,
          ),
        );
      }
    }

    await _http.postMultipart(
      endpoint: '/publicacao',
      fields: fields,
      files: arquivos,
    );
  }
}