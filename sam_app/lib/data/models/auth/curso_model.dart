class CursoModel {
  final int idCurso;
  final int periodo;
  final String nomeCurso;

  CursoModel({required this.idCurso, required this.periodo, required this.nomeCurso});

  factory CursoModel.fromMap(Map<String, dynamic> map) {
    return CursoModel(
      idCurso: map['id_curso'],
      periodo: map['periodo'],
      nomeCurso: map['nome_curso'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id_curso': idCurso,
      'periodo': periodo,
      'nome_curso': nomeCurso,
    };
  }
}