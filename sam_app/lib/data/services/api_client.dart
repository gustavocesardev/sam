import 'package:sam_app/core/exceptions/api_exception.dart';
import 'package:sam_app/data/services/http_service.dart';

class ApiClient {
  final HttpService _http = HttpService();

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      return await _http.post('/login', body: {
        'email': email,
        'password': password,
      });
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Erro ao realizar login.');
    }
  }

  Future<Map<String, dynamic>> refreshToken(String refreshToken) async {
    try {
      return await _http.post('/refresh-token', body: {
        'refresh_token': refreshToken,
      });
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Erro ao atualizar token.');
    }
  }
}