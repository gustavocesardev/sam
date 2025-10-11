import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'custom_text_form_field.dart';

class CustomDatePickerField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final DateTime? firstDate;
  final DateTime? lastDate;
  final bool disablePastDates;
  final bool readOnly;
  final void Function(DateTime)? onDateSelected;

  const CustomDatePickerField({
    super.key,
    required this.controller,
    required this.label,
    required this.hint,
    this.firstDate,
    this.lastDate,
    this.disablePastDates = false,
    this.readOnly = false,
    this.onDateSelected,
  });

  Future<void> _selectDate(BuildContext context) async {
    if (readOnly) return; // bloqueia abertura se for somente visualização

    DateTime initial;
    if (controller.text.isNotEmpty) {
      try {
        initial = DateFormat('dd/MM/yyyy').parse(controller.text);
      } catch (_) {
        initial = DateTime.now();
      }
    } else {
      initial = DateTime.now();
    }

    final DateTime start = disablePastDates
        ? DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day)
        : (firstDate ?? DateTime(2000));
    final DateTime end = lastDate ?? DateTime(2100);

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: initial.isBefore(start) ? start : initial,
      firstDate: start,
      lastDate: end,
    );

    if (picked != null) {
      controller.text = DateFormat('dd/MM/yyyy').format(picked);
      onDateSelected?.call(picked);
    }
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: readOnly ? null : () => _selectDate(context),
      child: AbsorbPointer(
        absorbing: true, // 👈 impede qualquer interação direta com o campo
        child: CustomTextFormField(
          controller: controller,
          label: label,
          hint: hint,
          isRequired: true,
          readOnly: true, // 👈 impede teclado
          enabled: !readOnly, // desativa visualmente se for leitura
        ),
      ),
    );
  }
}
