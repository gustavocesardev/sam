import 'dart:io';

import 'package:sam_app/data/models/user_detail_model.dart';
import 'package:sam_app/data/services/user_service.dart';

class UserRepository {
  final UserService service = UserService();

  Future<UserDetailModel?> getUserDetails(int id) async {
    return await service.getUserDetails(id);
  }

  Future<void> updateUser({
    required int id,
    required String name,
    required String biografia,
    File? image,
    required int anoInicio,
    required int anoFim,
  }) async {
    await service.updateUser(
      id: id,
      name: name,
      biografia: biografia,
      image: image,
      anoInicio: anoInicio,
      anoFim: anoFim,
    );
  }

}