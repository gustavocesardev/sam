import 'package:sam_app/data/models/auth/curso_model.dart';
import 'package:sam_app/data/models/auth/instituicao_model.dart';

class AuthUserModel {
  final int id;
  final String email;
  final CursoModel curso;
  final InstituicaoModel instituicao;

  AuthUserModel({
    required this.id,
    required this.email,
    required this.curso,
    required this.instituicao,
  });

  factory AuthUserModel.fromMap(Map<String, dynamic> map) {
    return AuthUserModel(
      id: map['id'],
      email: map['email'],
      curso: CursoModel.fromMap(map['curso']),
      instituicao: InstituicaoModel.fromMap(map['instituicao']),
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'email': email,
      'curso': curso.toMap(),
      'instituicao': instituicao.toMap(),
    };
  }
}
