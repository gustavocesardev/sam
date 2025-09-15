class ArtigoModel {
  final int id;
  final int idUsuario;
  final String nome;
  final String anoCurso;
  final String titulo;
  final String palavrasChave;
  final List<dynamic> conteudo;
  final String? pdf;
  final String publicadoEm;
  final String criadoEm;

  ArtigoModel({
    required this.id,
    required this.idUsuario,
    required this.nome,
    required this.anoCurso,
    required this.titulo,
    required this.palavrasChave,
    required this.conteudo,
    required this.pdf,
    required this.publicadoEm,
    required this.criadoEm,
  });

  factory ArtigoModel.fromJson(Map<String, dynamic> json) {
    return ArtigoModel(
      id: json['id'],
      idUsuario: json['id_usuario'],
      nome: json['nome'],
      anoCurso: json['ano_curso'],
      titulo: json['titulo'],
      palavrasChave: json['palavras_chave'] ?? '',
      conteudo: List<dynamic>.from(json['conteudo'] ?? []),
      pdf: json['pdf'],
      publicadoEm: json['publicaco_em'],
      criadoEm: json['created_at'],
    );
  }
}
