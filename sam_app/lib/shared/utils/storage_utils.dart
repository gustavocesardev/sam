import 'package:sam_app/data/storage/auth_storage_service.dart';

class StorageUtils {
  static Future<int?> getUserId() async {
    final user = await AuthStorageService.getStoredUser();
    if (user == null) return null;
    return user.id;
  }

  static Future<int?> getIdCurso() async {
    final user = await AuthStorageService.getStoredUser();
    if (user == null) return null;
    return user.curso.idCurso;
  }
}
