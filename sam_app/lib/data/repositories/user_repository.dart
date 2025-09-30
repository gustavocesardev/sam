import 'package:sam_app/data/models/user_detail_model.dart';
import 'package:sam_app/data/services/user_service.dart';

class UserRepository {
  final UserService service = UserService();

  Future<UserDetailModel?> getUserDetails(int id) async {
    return await service.getUserDetails(id);
  }
}