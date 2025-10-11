import 'package:flutter/material.dart';

class CustomDropdown<T> extends StatelessWidget {
  final T? valorSelecionado;
  final ValueChanged<T?>? onChanged;
  final List<DropdownMenuItem<T>> itens;
  final String? label;
  final bool enabled;

  const CustomDropdown({
    super.key,
    required this.valorSelecionado,
    required this.itens,
    this.onChanged,
    this.label,
    this.enabled = true,
  });

  @override
  Widget build(BuildContext context) {
    final T? valorInicial =
        valorSelecionado ?? (itens.isNotEmpty ? itens.first.value : null);

    return DropdownButtonFormField<T>(
      value: valorInicial,
      decoration: InputDecoration(
        labelText: label ?? 'Selecione',
        border: const OutlineInputBorder(),
        enabledBorder: OutlineInputBorder(
          borderSide: BorderSide(
            color: Theme.of(context)
                    .inputDecorationTheme
                    .enabledBorder
                    ?.borderSide
                    .color ??
                Colors.grey,
            width: 0.75,
          ),
        ),
        focusedBorder: OutlineInputBorder(
          borderSide: BorderSide(
            color: Theme.of(context).colorScheme.secondary,
            width: 1,
          ),
        ),
        disabledBorder: OutlineInputBorder(
          borderSide: BorderSide(
            color: Colors.grey.shade400,
            width: 0.75,
          ),
        ),
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
      ),
      dropdownColor: Theme.of(context).scaffoldBackgroundColor,
      items: itens,
      onChanged: enabled ? onChanged : null,
    );
  }
}
