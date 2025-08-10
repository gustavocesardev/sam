import 'package:flutter/material.dart';
import 'package:sam_app/data/enums/tipo_formulario_enum.dart';
import 'package:sam_app/domain/viewmodels/formulario/formulario_form_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/presentation/widgets/input/custom_dropdown.dart';
import 'package:sam_app/presentation/widgets/input/custom_text_form_field.dart';

class FormularioFormPage extends StatefulWidget {
  final int? idFormulario;

  const FormularioFormPage({super.key, this.idFormulario});

  @override
  State<FormularioFormPage> createState() => _FormularioFormPageState();
}

class _FormularioFormPageState extends State<FormularioFormPage> {
  late FormularioFormViewModel vm;

  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    vm = FormularioFormViewModel(idFormulario: widget.idFormulario);
    vm.addListener(() {
      if (mounted) setState(() {});
    });
    vm.init();
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
        textAppBar:
            widget.idFormulario == null ? 'Novo formulário' : 'Editar formulário',
      ),
      body: vm.isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
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
                        ],
                      ),
                      const SizedBox(height: 175),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          _buildButton(
                            label: "Cancelar",
                            color: Colors.red.shade700,
                            icon: Icons.close,
                            onPressed: () => Navigator.pop(context),
                          ),
                          _buildButton(
                            label: "Finalizar",
                            color: Theme.of(context).colorScheme.primary,
                            icon: Icons.arrow_forward,
                            onPressed: () {
                              if (_formKey.currentState!.validate()) {
                                vm.salvarFormulario();
                              }
                            },
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }

  Widget _buildButton({
    required String label,
    required Color color,
    required IconData icon,
    required VoidCallback onPressed,
  }) {
    return ElevatedButton.icon(
      onPressed: onPressed,
      style: ElevatedButton.styleFrom(
        backgroundColor: color,
        padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      ),
      icon: Icon(icon, color: Colors.white),
      label: Text(
        label,
        style: const TextStyle(color: Colors.white, fontSize: 16),
      ),
    );
  }
}