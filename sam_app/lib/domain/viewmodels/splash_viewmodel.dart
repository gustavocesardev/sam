import 'package:sam_app/core/routing/app_routes.dart';
import 'package:sam_app/data/services/api_client.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';

class SplashViewModel {
  final AuthStorageService storageService;
  final ApiClient apiClient = ApiClient();

  SplashViewModel(this.storageService);

  Future<String> initializeApp() async {
    await Future.delayed(const Duration(seconds: 2));

    final isLoggedIn = storageService.isLoggedIn;

    if (!isLoggedIn) {
      return AppRoutes.login;
    }

    try {
      final result = await apiClient.refreshToken(storageService.refreshToken!);

      final accessToken = result['content']['access_token'];
      final refreshToken = result['content']['refresh_token'];

      await storageService.saveTokenData(
        accessToken: accessToken,
        refreshToken: refreshToken,
      );

      return AppRoutes.home;
    } catch (_) {
      storageService.clear();
      return AppRoutes.login;
    }
  }
}
