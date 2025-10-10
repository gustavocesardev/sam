class InstituicaoModel {
  final int idInstituicao;
  final String nomeInstituicao;
  final String imagemInstituicao;
  final String dominioInstituicao;

  InstituicaoModel({
    required this.idInstituicao,
    required this.nomeInstituicao,
    required this.imagemInstituicao,
    required this.dominioInstituicao,
  });

  factory InstituicaoModel.fromAuthJson(Map<String, dynamic> json) {
    return InstituicaoModel(
      idInstituicao: json['id_instituicao'],
      nomeInstituicao: json['nome_instituicao'],
      imagemInstituicao: json['imagem_instituicao'],
      dominioInstituicao: json['dominio_instituicao'],
    );
  }

  factory InstituicaoModel.fromJson(Map<String, dynamic> json) {
    return InstituicaoModel(
      idInstituicao: json['id'],
      nomeInstituicao: json['razao_social'],
      imagemInstituicao: json['imagem'],
      dominioInstituicao: json['dominio'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id_instituicao': idInstituicao,
      'nome_instituicao': nomeInstituicao,
      'imagem_instituicao': imagemInstituicao,
      'dominio_instituicao': dominioInstituicao,
    };
  }
}