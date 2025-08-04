class CursoModel {
  final int id;
  final String nomeCurso;
  final int duracaoMinima;
  final int duracaoMaxima;
  final String situacao;

  CursoModel({
    required this.id,
    required this.nomeCurso,
    required this.duracaoMinima,
    required this.duracaoMaxima,
    required this.situacao,
  });

  factory CursoModel.fromJson(Map<String, dynamic> json) {
    return CursoModel(
      id: json['id'],
      nomeCurso: json['nome_curso'],
      duracaoMinima: json['duracao_minima'],
      duracaoMaxima: json['duracao_maxima'],
      situacao: json['situacao'],
    );
  }
}
