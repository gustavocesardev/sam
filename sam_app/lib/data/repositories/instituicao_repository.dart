import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:sam_app/data/models/auth/instituicao_model.dart';
import 'package:sam_app/shared/constants.dart';

class InstituicaoRepository {
  Future<List<InstituicaoModel>> getInstituicoes() async {
    final response = await http.get(Uri.parse('$baseUrl/instituicao'));

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final List content = data['content'];
      return content.map((e) => InstituicaoModel.fromJson(e)).toList();
    } else {
      throw Exception('Erro ao buscar instituições');
    }
  }
}
