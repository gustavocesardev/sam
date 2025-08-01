import 'dart:convert';
import 'package:sam_app/data/models/auth/auth_user_model.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthStorageService {
  final SharedPreferences _prefs;

  AuthStorageService(this._prefs);

  String? get accessToken => _prefs.getString('access_token');
  String? get refreshToken => _prefs.getString('refresh_token');

  bool get isLoggedIn => accessToken != null;

  Future<void> saveAuthData({
    required String accessToken,
    required String refreshToken,
    required Map<String, dynamic> userJson,
  }) async {
    saveTokenData(accessToken: accessToken, refreshToken: refreshToken);
    await _prefs.setString('user', jsonEncode(userJson));
  }

  Future<void> saveTokenData({
    required String accessToken,
    required String refreshToken,
  }) async {
    await _prefs.setString('access_token', accessToken);
    await _prefs.setString('refresh_token', refreshToken);
  }

  void clear() {
    _prefs.remove('access_token');
    _prefs.remove('refresh_token');
    _prefs.remove('user');
  }

  static Future<AuthStorageService> init() async {
    final prefs = await SharedPreferences.getInstance();
    return AuthStorageService(prefs);
  }

  static Future<String?> getStoredAccessToken() async {
    final storage = await AuthStorageService.init();
    return storage.accessToken;
  }

  static Future<AuthUserModel?> getStoredUser() async {
    final storage = await AuthStorageService.init();
    final jsonString = storage._prefs.getString('user');

    if (jsonString == null) return null;

    final Map<String, dynamic> map = jsonDecode(jsonString);
    return AuthUserModel.fromMap(map);
  }
}
