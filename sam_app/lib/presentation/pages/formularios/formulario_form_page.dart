import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:sam_app/data/enums/tipo_formulario_enum.dart';
import 'package:sam_app/domain/viewmodels/formulario/formulario_form_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/presentation/widgets/buttons/custom_icon_button.dart';
import 'package:sam_app/presentation/widgets/buttons/loading_button.dart';
import 'package:sam_app/presentation/widgets/input/custom_date_picker_field.dart';
import 'package:sam_app/presentation/widgets/input/custom_dropdown.dart';
import 'package:sam_app/presentation/widgets/input/custom_text_form_field.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class FormularioFormPage extends StatefulWidget {
  final int idUsuario;
  final int? idFormulario;

  const FormularioFormPage({
    super.key,
    this.idFormulario,
    required this.idUsuario,
  });

  @override
  State<FormularioFormPage> createState() => _FormularioFormPageState();
}

class _FormularioFormPageState extends State<FormularioFormPage> {
  late FormularioFormViewModel vm;
  final _formKey = GlobalKey<FormState>();

  bool get isEditing => widget.idFormulario != null;

  bool get isExpired {
    final text = vm.dataLimiteController.text.trim();
    if (text.isEmpty) return false;

    try {
      final parsed = DateFormat('dd/MM/yyyy').parse(text);
      final dateOnly = DateTime(parsed.year, parsed.month, parsed.day);
      final now = DateTime.now();
      final todayOnly = DateTime(now.year, now.month, now.day);
      return dateOnly.isBefore(todayOnly);
    } catch (_) {
      return false;
    }
  }

  @override
  void initState() {
    super.initState();
    vm = FormularioFormViewModel(
      idUsuario: widget.idUsuario,
      idFormulario: widget.idFormulario,
    );

    WidgetsBinding.instance.addPostFrameCallback((_) {
      vm.init();
    });
  }

  @override
  void dispose() {
    vm.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      appBar: SimpleAppBar(
        textAppBar: isEditing ? 'Editar formulário' : 'Novo formulário',
      ),
      body: AnimatedBuilder(
        animation: vm,
        builder: (context, _) {
          if (vm.isLoadingData) {
            return const Center(child: CircularProgressIndicator());
          }

          final somenteVisualizacao = isEditing && isExpired;

          return SingleChildScrollView(
            child: Padding(
              padding: const EdgeInsets.only(
                top: 20,
                bottom: 60,
                left: 28,
                right: 28,
              ),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // 🟥 Banner de aviso se expirado
                        if (somenteVisualizacao)
                          Container(
                            margin: const EdgeInsets.only(bottom: 40),
                            padding: const EdgeInsets.all(14),
                            decoration: BoxDecoration(
                              color: Colors.orange.shade50,
                              borderRadius: BorderRadius.circular(8),
                              border: Border.all(color: Colors.orange.shade200),
                            ),
                            child: Row(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Icon(
                                  Icons.warning_amber_rounded,
                                  color: Colors.orange.shade600,
                                  size: 24,
                                ),
                                const SizedBox(width: 10),
                                Expanded(
                                  child: Text(
                                    'Este formulário atingiu a data limite e não pode mais ser editado.',
                                    style: TextStyle(
                                      color: Colors.orange.shade700,
                                      fontSize: 14,
                                      fontWeight: FontWeight.w500,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),

                        CustomTextFormField(
                          controller: vm.tituloController,
                          label: 'Título*',
                          hint: 'Informe o título',
                          readOnly: somenteVisualizacao,
                        ),
                        const SizedBox(height: 28),
                        CustomTextFormField(
                          controller: vm.descricaoController,
                          label: 'Descrição*',
                          hint: 'Informe a descrição',
                          maxLines: 10,
                          maxLength: 250,
                          readOnly: somenteVisualizacao,
                        ),
                        const SizedBox(height: 28),
                        CustomTextFormField(
                          controller: vm.googleFormsController,
                          label: 'Google Forms*',
                          hint: 'Informe o link do Google Forms',
                          readOnly: somenteVisualizacao,
                        ),
                        const SizedBox(height: 28),
                        CustomDropdown<String>(
                          valorSelecionado: vm.tipoSelecionado,
                          onChanged: somenteVisualizacao
                              ? null
                              : vm.setTipoSelecionado,
                          label: 'Tipo*',
                          itens: TipoFormularioEnum.values
                              .map(
                                (tipo) => DropdownMenuItem(
                                  value: tipo.codigo,
                                  child: Text(tipo.descricao),
                                ),
                              )
                              .toList(),
                        ),
                        const SizedBox(height: 28),

                        // Campo de data limite
                        CustomDatePickerField(
                          controller: vm.dataLimiteController,
                          label: 'Data Limite*',
                          hint: 'Selecione a data limite',
                          disablePastDates: true,
                          readOnly: isEditing || somenteVisualizacao,
                        ),

                        if (isEditing)
                          Padding(
                            padding: const EdgeInsets.only(top: 6.0, left: 4),
                            child: Text(
                              'A data limite não pode ser alterada.',
                              style: const TextStyle(
                                fontSize: 13,
                                color: Colors.grey,
                              ),
                            ),
                          ),
                      ],
                    ),
                    SizedBox(height: somenteVisualizacao ? 50 : 110),

                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        // Botão de Excluir ou Cancelar
                        Expanded(
                          child: vm.idFormulario != null
                              ? (vm.isLoadingExclude
                                    ? const LoadingButtonSimple()
                                    : CustomIconButton(
                                        label: "Excluir",
                                        color: Colors.red.shade700,
                                        icon: Icons.close,
                                        onPressed: () async {
                                          try {
                                            await vm.excluirFormulario();
                                            if (context.mounted) {
                                              TopSnackBar.show(
                                                context,
                                                'Formulário excluído com sucesso!',
                                                color: Colors.orange[800],
                                              );
                                              Navigator.pop(context, true);
                                            }
                                          } catch (error) {
                                            if (context.mounted) {
                                              TopSnackBar.show(
                                                context,
                                                error.toString(),
                                                color: Colors.red[700],
                                              );
                                            }
                                          }
                                        },
                                      ))
                              : CustomIconButton(
                                  label: "Cancelar",
                                  color: Colors.red.shade700,
                                  icon: Icons.close,
                                  onPressed: () async => Navigator.pop(context),
                                ),
                        ),

                        if (!somenteVisualizacao) const SizedBox(width: 16),

                        // Botão Finalizar
                        if (!somenteVisualizacao)
                          Expanded(
                            child: vm.isLoading
                                ? const LoadingButtonSimple()
                                : CustomIconButton(
                                    label: "Finalizar",
                                    color: Theme.of(
                                      context,
                                    ).colorScheme.primary,
                                    icon: Icons.arrow_forward,
                                    onPressed: () async {
                                      if (_formKey.currentState!.validate()) {
                                        try {
                                          await vm.salvarFormulario();
                                          if (context.mounted) {
                                            TopSnackBar.show(
                                              context,
                                              'Formulário gravado com sucesso!',
                                            );
                                            Navigator.pop(context, true);
                                          }
                                        } catch (error) {
                                          if (context.mounted) {
                                            TopSnackBar.show(
                                              context,
                                              error.toString(),
                                              color: Colors.red[700],
                                            );
                                          }
                                        }
                                      }
                                    },
                                  ),
                          ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}
