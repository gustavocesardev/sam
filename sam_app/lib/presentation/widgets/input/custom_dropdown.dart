import 'package:flutter/material.dart';

class CustomDropdown<T> extends StatelessWidget {
  final T? valorSelecionado;
  final ValueChanged<T?> onChanged;
  final List<DropdownMenuItem<T>> itens;
  final String? label;

  const CustomDropdown({
    super.key,
    required this.valorSelecionado,
    required this.onChanged,
    required this.itens,
    this.label,
  });

  @override
  Widget build(BuildContext context) {
    // Valor padrão é a primeira opção da lista, se valorSelecionado for null
    final T? valorInicial = valorSelecionado ?? (itens.isNotEmpty ? itens.first.value : null);

    return DropdownButtonFormField<T>(
      value: valorInicial,
      decoration: InputDecoration(
        labelText: label ?? 'Selecione',
        border: const OutlineInputBorder(),
        enabledBorder: OutlineInputBorder(
          borderSide: BorderSide(
            color: Theme.of(context)
                .inputDecorationTheme
                .enabledBorder!
                .borderSide
                .color,
            width: 1,
          ),
        ),
        focusedBorder: OutlineInputBorder(
          borderSide: BorderSide(
            color: Theme.of(context).colorScheme.secondary,
            width: 2,
          ),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
      ),
      dropdownColor: Theme.of(context).colorScheme.primary,
      items: itens,
      onChanged: onChanged,
    );
  }
}