import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:sam_app/core/exceptions/api_exception.dart';
import 'package:sam_app/shared/constants.dart';

class RegisterService {
  Future<void> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required int cursoId,
    required int anoInicio,
    required int anoFim,
    required int instituicaoId,
  }) async {
    try {

      await http.post(
        Uri.parse('$baseUrl/register'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
          'id_curso': cursoId,
          'ano_inicio_curso': anoInicio,
          'ano_fim_curso': anoFim,
          'id_instituicao': instituicaoId,
        }),
      );
      
    } catch (e) {
      if (e is ApiException) rethrow;
      throw Exception('Erro ao efetuar o registrar');
    }
  }
}
