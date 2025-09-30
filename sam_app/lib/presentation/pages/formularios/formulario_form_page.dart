import 'package:flutter/material.dart';
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
        textAppBar: widget.idFormulario == null
            ? 'Novo formulário'
            : 'Editar formulário',
      ),
      body: AnimatedBuilder(
        animation: vm,
        builder: (context, _) {
          if (vm.isLoadingData) {
            return const Center(child: CircularProgressIndicator());
          }
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
                        CustomTextFormField(
                          controller: vm.tituloController,
                          label: 'Título*',
                          hint: 'Informe o título',
                        ),
                        const SizedBox(height: 28),
                        CustomTextFormField(
                          controller: vm.descricaoController,
                          label: 'Descrição*',
                          hint: 'Informe a descrição',
                          maxLines: 10,
                          maxLength: 250,
                        ),
                        const SizedBox(height: 28),
                        CustomTextFormField(
                          controller: vm.googleFormsController,
                          label: 'Google forms*',
                          hint: 'Informe o link do Google Forms',
                        ),
                        const SizedBox(height: 28),
                        CustomDropdown<String>(
                          valorSelecionado: vm.tipoSelecionado,
                          onChanged: vm.setTipoSelecionado,
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
                        CustomDatePickerField(
                          controller: vm.dataLimiteController,
                          label: 'Data Limite*',
                          hint: 'Selecione a data limite',
                          disablePastDates: true,
                        ),
                      ],
                    ),
                    const SizedBox(height: 110),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        vm.idFormulario != null
                            ? vm.isLoadingExclude
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
                                              'Formulario excluído com sucesso!',
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
                                    )
                            : CustomIconButton(
                                label: "Cancelar",
                                color: Colors.red.shade700,
                                icon: Icons.close,
                                onPressed: () async => Navigator.pop(context),
                              ),
                        vm.isLoading
                            ? const LoadingButtonSimple()
                            : CustomIconButton(
                                label: "Finalizar",
                                color: Theme.of(context).colorScheme.primary,
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
