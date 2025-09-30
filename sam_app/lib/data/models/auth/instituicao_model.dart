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

  factory InstituicaoModel.fromMap(Map<String, dynamic> map) {
    return InstituicaoModel(
      idInstituicao: map['id_instituicao'],
      nomeInstituicao: map['nome_instituicao'],
      imagemInstituicao: map['imagem_instituicao'],
      dominioInstituicao: map['dominio_instituicao'],
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