class FormularioRequest {
  final int? idFormulario;
  final int idUsuario;
  final String titulo;
  final String descricao;
  final String tipo;
  final String linkForms;
  final String dataLimite;

  FormularioRequest({
    this.idFormulario,
    required this.idUsuario,
    required this.titulo,
    required this.descricao,
    required this.tipo,
    required this.linkForms,
    required this.dataLimite,
  });
}