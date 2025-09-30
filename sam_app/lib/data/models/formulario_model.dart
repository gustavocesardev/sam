import 'package:sam_app/data/enums/tipo_formulario_enum.dart';

class FormularioModel {
  final int id;
  final int idUsuario;
  final String nome;
  final String periodo;
  final String curso;
  final String titulo;
  final String descricao;
  final TipoFormularioEnum tipo;
  final String situacao;
  final String linkForms;
  final DateTime dataLimite;
  final String criadoEm;

  FormularioModel({
    required this.id,
    required this.idUsuario,
    required this.nome,
    required this.periodo,
    required this.curso,
    required this.titulo,
    required this.descricao,
    required this.tipo,
    required this.situacao,
    required this.linkForms,
    required this.dataLimite,
    required this.criadoEm,
  });

  factory FormularioModel.fromJson(Map<String, dynamic> json) {
    final partesData = json['data_limite'].split('-');
    final dataLimite = DateTime(
      int.parse(partesData[2]),
      int.parse(partesData[1]),
      int.parse(partesData[0]),
    );

    return FormularioModel(
      id: json['id'],
      idUsuario: json['id_usuario'],
      nome: json['nome'],
      periodo: json['periodo'],
      curso: json['curso'],
      titulo: json['titulo'],
      descricao: json['descricao'],
      tipo: TipoFormularioEnum.fromCodigo(json['tipo'])!,
      situacao: json['situacao'],
      linkForms: json['link_forms'],
      dataLimite: dataLimite,
      criadoEm: json['criado_em'],
    );
  }
}
