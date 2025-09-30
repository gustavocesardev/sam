import 'package:sam_app/data/models/user_detail_model.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class UserService {
  final HttpService _http = HttpService();

  Future<UserModel?> getUser(int id) async {
    final response = await _http.get('/user/$id');

    final user = UserModel.fromJson(response['content']);
    return user;
  }

  Future<UserDetailModel?> getUserDetails(int id) async {
    final response = await _http.get('/user/$id/details');
    final user = UserDetailModel.fromJson(response['content']);
    return user;
  }
}
