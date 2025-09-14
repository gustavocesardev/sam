import 'dart:convert';
import 'dart:typed_data';
import 'package:http/http.dart' as http;
import 'package:sam_app/core/exceptions/api_exception.dart';
import 'package:sam_app/shared/constants.dart';
import 'package:sam_app/shared/utils/api_message_utils.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';

class HttpService {
  Future<Map<String, dynamic>> get(String endpoint) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final token = await _getToken();

    final response = await http.get(
      uri,
      headers: {
        'Content-Type': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
    );

    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> post(String endpoint, {Map<String, dynamic>? body}) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final token = await _getToken();

    final response = await http.post(
      uri,
      headers: {
        'Content-Type': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
      body: jsonEncode(body),
    );

    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> put(String endpoint, {Map<String, dynamic>? body}) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final token = await _getToken();

    final response = await http.put(
      uri,
      headers: {
        'Content-Type': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
      body: jsonEncode(body),
    );

    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> delete(String endpoint) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final token = await _getToken();

    final response = await http.delete(
      uri,
      headers: {
        'Content-Type': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
    );

    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> postMultipart({
    required String endpoint,
    required Map<String, String> fields,
    List<http.MultipartFile>? files,
  }) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final token = await _getToken();

    final request = http.MultipartRequest('POST', uri)
      ..fields.addAll(fields);

    if (token != null) {
      request.headers['Authorization'] = 'Bearer $token';
    }

    if (files != null && files.isNotEmpty) {
      request.files.addAll(files);
    }

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);

    return _handleResponse(response);
  }

  Future<Uint8List> fetchImageBytes(String url) async {
    final token = await _getToken();
    final uri = Uri.parse(url);
    final response = await http.get(
      uri,
      headers: {
        if (token != null) 'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      return response.bodyBytes;
    } else {
      throw Exception('Falha ao carregar imagem');
    }
  }

  Map<String, dynamic> _handleResponse(http.Response response) {
    final decoded = jsonDecode(response.body);
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return decoded;
    } else {
      final message = ApiMessageUtils.extractMessageFromResponse(decoded['message']);
      throw ApiException(message);
    }
  }

  Future<String?> _getToken() async {
    return await AuthStorageService.getStoredAccessToken();
  }
}