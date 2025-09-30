enum TipoFormularioEnum {
  ge('GE', 'Geral'),
  ac('AC', 'Acadêmico');

  final String codigo;
  final String descricao;

  const TipoFormularioEnum(this.codigo, this.descricao);

  static TipoFormularioEnum? fromCodigo(String codigo) {
    return TipoFormularioEnum.values.firstWhere(
      (e) => e.codigo == codigo,
      orElse: () => TipoFormularioEnum.ge,
    );
  }
}