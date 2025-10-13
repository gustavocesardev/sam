import 'dart:io';

import 'package:http/http.dart' as http;
import 'package:sam_app/data/models/user_detail_model.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class UserService {
  final HttpService _http = HttpService();

  Future<UserModel?> getUser(int id) async {
    final response = await _http.get('/user/$id');

    final user = UserModel.fromJson(response['content']);
    return user;
  }

  Future<UserDetailModel?> getUserDetails(int id) async {
    final response = await _http.get('/user/$id/details');
    final user = UserDetailModel.fromJson(response['content']);
    return user;
  }

  Future<void> updateUser({
    required int id,
    required String name,
    required String biografia,
    required int anoInicio,
    required int anoFim,
    File? image,
  }) async {
    final fields = {
      '_method': 'PUT',
      'name': name,
      'biografia': biografia,
      'ano_inicio_curso': anoInicio.toString(),
      'ano_fim_curso': anoFim.toString(),
    };

    final files = <http.MultipartFile>[];

    if (image != null) {
      final fileName = image.path.split('/').last;
      files.add(
        await http.MultipartFile.fromPath(
          'foto_perfil',
          image.path,
          filename: fileName,
        ),
      );
    }

    await _http.postMultipart(
      endpoint: '/user/$id',
      fields: fields,
      files: files,
    );
  }
}
