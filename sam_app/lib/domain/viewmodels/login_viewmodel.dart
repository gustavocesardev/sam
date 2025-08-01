import 'package:flutter/material.dart';
import 'package:sam_app/data/services/api_client.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';

class LoginViewModel extends ChangeNotifier {
  final ApiClient _api = ApiClient();
  final AuthStorageService _authStorage;

  LoginViewModel(this._authStorage);

  bool _loading = false;
  String? _errorMessage;

  bool get loading => _loading;
  String? get errorMessage => _errorMessage;

  Future<void> loginAndStore(String email, String password) async {
    _setLoading(true);
    _setError(null);

    try {
      final data = await _api.login(email, password);

      final content = data['content'];
      final user = content['user'];
      final token = content['token'];

      await _authStorage.saveAuthData(
        accessToken: token['access_token'],
        refreshToken: token['refresh_token'],
        userJson: user,
      );
    } catch (e) {
      _setError(e.toString());
    } finally {
      _setLoading(false);
    }
  }

  void _setLoading(bool value) {
    _loading = value;
    notifyListeners();
  }

  void _setError(String? message) {
    _errorMessage = message;
    notifyListeners();
  }

  void clearError() {
    _setError(null);
  }
}