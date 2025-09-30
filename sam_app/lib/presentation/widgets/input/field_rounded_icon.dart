import 'package:flutter/material.dart';

class FielRoundedIcon extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final IconData icon;
  final ValueChanged<String>? onChanged;

  const FielRoundedIcon({
    super.key,
    required this.controller,
    this.label = 'Pesquisar',
    required this.icon,
    this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    final borderRadius = BorderRadius.circular(20);

    return TextField(
      controller: controller,
      onChanged: onChanged,
      decoration: InputDecoration(
        labelText: label,
        suffixIcon: Icon(icon, color: Theme.of(context).colorScheme.secondary),
        border: OutlineInputBorder(borderRadius: borderRadius),
        contentPadding: const EdgeInsets.all(12),
        enabledBorder: OutlineInputBorder(
          borderRadius: borderRadius,
          borderSide: BorderSide(
            color:
                Theme.of(
                  context,
                ).inputDecorationTheme.enabledBorder?.borderSide.color ??
                Colors.grey,
            width: 1.5,
          ),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: borderRadius,
          borderSide: BorderSide(
            color: Theme.of(context).colorScheme.secondary,
            width: 1.75,
          ),
        ),
      ),
    );
  }
}
