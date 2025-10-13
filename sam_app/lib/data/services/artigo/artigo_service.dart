import 'dart:convert';
import 'dart:io';

import 'package:http/http.dart' as http;
import 'package:sam_app/data/models/artigo_model.dart';
import 'package:sam_app/data/requests/artigo_request.dart';
import 'package:sam_app/data/services/http_service.dart';

import 'package:path_provider/path_provider.dart';
import 'package:open_file/open_file.dart';

import 'package:sam_app/shared/constants.dart';

class ArtigoService {
  final HttpService _http = HttpService();

  Future<List<ArtigoModel>> fetchFiltrados({
    int page = 1,
    Map<String, dynamic>? filtros,
  }) async {
    final response = await _http.post(
      '/artigo-universitario/filtrar?page=$page&limite=7',
      body: filtros,
    );

    final list = response['content'] as List;
    return list.map((e) => ArtigoModel.fromJson(e)).toList();
  }

  Future<List<ArtigoModel>> fetchCriados({int page = 1}) async {
    final response = await _http.get(
      '/artigo-universitario/criados?page=$page&limite=7',
    );

    final list = response['content'] as List;
    return list.map((e) => ArtigoModel.fromJson(e)).toList();
  }

  Future<ArtigoModel> index({required int id}) async {
    final response = await _http.get('/artigo-universitario/$id');
    final json = response['content'];
    return ArtigoModel.fromJson(json);
  }

  Future<void> store({required ArtigoRequest request}) async {
    final fields = <String, String>{
      'id_usuario': request.idUsuario.toString(),
      'titulo': request.titulo,
      'conteudo': jsonEncode(request.conteudo),
      'palavras_chave': request.hashtags,
    };

    if (request.idArtigo != null) {
      fields['_method'] = 'PUT';
    }

    final files = <http.MultipartFile>[];
    if (request.pdfFile != null) {
      files.add(
        await http.MultipartFile.fromPath('pdf', request.pdfFile!.path),
      );
    }

    final endpoint = request.idArtigo != null
        ? '/artigo-universitario/${request.idArtigo}'
        : '/artigo-universitario';

    await _http.postMultipart(endpoint: endpoint, fields: fields, files: files);
  }

  Future<void> delete({required int id}) async {
    await _http.delete('/artigo-universitario/$id');
  }

  Future<void> downloadPdf(
    String url,
    String nomeArquivo, {
    int maxRetries = 10,
  }) async {
    int attempt = 0;
    while (attempt < maxRetries) {
      final client = http.Client();

      try {

        final request = http.Request(
          'GET',
          Uri.parse('$baseUrl/file/document/$url'),
        );
        final streamedResponse = await client.send(request);

        if (streamedResponse.statusCode != 200) {
          throw Exception(
            'Falha ao baixar PDF: ${streamedResponse.statusCode}',
          );
        }

        final dir = await getApplicationDocumentsDirectory();
        final safeFileName = nomeArquivo.replaceAll(
          RegExp(r'[\\/:*?"<>|]'),
          '_',
        );
        final file = File('${dir.path}/$safeFileName.pdf');
        final sink = file.openWrite();

        await streamedResponse.stream.pipe(sink);
        await sink.close();

        await OpenFile.open(file.path);
        return;
        
      } catch (e) {
        attempt++;
        if (attempt >= maxRetries) {
          throw Exception(
            'Falha ao baixar PDF após $maxRetries tentativas. Erro: $e',
          );
        }

        await Future.delayed(const Duration(seconds: 2));
      } finally {
        client.close();
      }
    }
  }
}
