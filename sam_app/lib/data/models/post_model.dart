class PostModel {
  final int id;
  final String nome;
  final String curso;
  final String texto;
  final List<String> imagens;
  final String criadoEm;
  final int curtidas;
  final int comentarios;
  final String? avatarEncrypted;
  final bool curtido;

  PostModel({
    required this.id,
    required this.nome,
    required this.curso,
    required this.texto,
    required this.imagens,
    required this.criadoEm,
    required this.curtidas,
    required this.comentarios,
    required this.curtido,
    this.avatarEncrypted,
  });

  factory PostModel.fromJson(Map<String, dynamic> json) {
    return PostModel(
      id: json["id_publicacao"],
      nome: json['nome'],
      curso: json['curso'],
      texto: json['texto'],
      imagens:
          (json['imagens'] as List<dynamic>?)
              ?.map((item) => item.toString())
              .toList() ??
          [],
      criadoEm: json['criado_em'],
      curtidas: json['qtde_curtidas'],
      comentarios: json['qtde_comentarios'],
      curtido: json['curtido'],
      avatarEncrypted: json['foto_usuario'],
    );
  }
}
