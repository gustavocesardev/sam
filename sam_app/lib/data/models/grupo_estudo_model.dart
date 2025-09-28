import 'package:sam_app/shared/constants.dart';

class GrupoEstudoModel {
  final int id;
  final int idCurso;
  final int idUsuario;
  final String nomeUsuario;
  final String cursoUsuario;
  final String criacao;
  final int? idMembro;
  final String nomeGrupo;
  final String descricao;
  final String hashtags;
  final String imagem;
  final String imagemHeader;
  final int qtdeMembros;

  GrupoEstudoModel({
    required this.id,
    required this.idCurso,
    required this.idUsuario,
    required this.nomeUsuario,
    required this.cursoUsuario,
    required this.idMembro,
    required this.nomeGrupo,
    required this.descricao,
    required this.hashtags,
    required this.imagem,
    required this.imagemHeader,
    required this.qtdeMembros,
    required this.criacao
  });

  factory GrupoEstudoModel.fromJson(Map<String, dynamic> json) {
    return GrupoEstudoModel(
      id: json['id_grupo_estudo'],
      idCurso: json['id_curso'],
      idUsuario: json['id_usuario'],
      nomeUsuario: json['nome'],
      cursoUsuario: json['curso'],
      idMembro: json['id_membro'],
      nomeGrupo: json['nome_grupo'],
      descricao: json['descricao'],
      hashtags: json['hashtags'],
      imagem: json['imagem'],
      imagemHeader: json['imagem_header'],
      qtdeMembros: json['qtde_membros'],
      criacao: json['criado_em']
    );
  }

  String get imagemHeaderUrl => '$baseUrl/file/image/$imagemHeader';
  String get imagemUrl => '$baseUrl/file/image/$imagem';
}
