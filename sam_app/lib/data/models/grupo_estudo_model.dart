class GrupoEstudoModel {
  final int id;
  final int idCurso;
  final int idUsuario;
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
    required this.nomeGrupo,
    required this.descricao,
    required this.hashtags,
    required this.imagem,
    required this.imagemHeader,
    required this.qtdeMembros
  });

  factory GrupoEstudoModel.fromJson(Map<String, dynamic> json) {
    return GrupoEstudoModel(
      id: json['id_grupo_estudo'],
      idCurso: json['id_curso'],
      idUsuario: json['id_usuario'],
      nomeGrupo: json['nome_grupo'],
      descricao: json['descricao'],
      hashtags: json['hashtags'],
      imagem: json['imagem'],
      imagemHeader: json['imagem_header'],
      qtdeMembros: json['qtde_membros']
    );
  }
}
