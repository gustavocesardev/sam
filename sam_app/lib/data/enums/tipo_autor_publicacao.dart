enum TipoAutorPublicacao {
  usuario('id_usuario'),
  membro('id_membro');

  final String atributo;

  const TipoAutorPublicacao(this.atributo);
}
