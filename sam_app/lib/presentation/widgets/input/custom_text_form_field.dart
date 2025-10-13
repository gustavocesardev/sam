import 'package:flutter/material.dart';

class CustomTextFormField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final bool isRequired;
  final int maxLines;
  final int? maxLength;
  final bool readOnly;
  final bool enabled;

  const CustomTextFormField({
    super.key,
    required this.controller,
    required this.label,
    required this.hint,
    this.isRequired = true,
    this.maxLines = 1,
    this.maxLength,
    this.readOnly = false,
    this.enabled = true,
  });

  @override
  Widget build(BuildContext context) {
    final borderRadius = BorderRadius.circular(10);

    return TextFormField(
      controller: controller,
      maxLines: maxLines,
      maxLength: maxLength,
      readOnly: readOnly,
      enabled: enabled,
      style: TextStyle(
        color: enabled
            ? Theme.of(context).textTheme.bodyLarge?.color
            : Colors.grey[500],
      ),
      decoration: InputDecoration(
        labelText: label,
        hintText: hint,
        alignLabelWithHint: true,
        hintStyle: TextStyle(
          color: Theme.of(context).colorScheme.secondary,
          fontSize: 14,
        ),
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
            width: 0.75,
          ),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: borderRadius,
          borderSide: BorderSide(
            color: Theme.of(context).colorScheme.secondary,
            width: 1,
          ),
        ),
        disabledBorder: OutlineInputBorder(
          borderRadius: borderRadius,
          borderSide: BorderSide(color: Colors.grey.shade400, width: 0.75),
        ),
      ),
      validator: (value) {
        if ((value == null || value.isEmpty) && isRequired && enabled) {
          return '$label é um campo obrigatório';
        }
        if (maxLength != null && value != null && value.length > maxLength!) {
          return '$label não pode exceder $maxLength caracteres';
        }
        return null;
      },
    );
  }
}
