import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:sam_app/domain/viewmodels/formulario/formulario_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:url_launcher/url_launcher.dart';

class FormularioPage extends StatefulWidget {
  final int idFormulario;

  const FormularioPage({super.key, required this.idFormulario});

  @override
  State<FormularioPage> createState() => _FormularioPageState();
}

class _FormularioPageState extends State<FormularioPage> {
  late FormularioViewmodel vm;

  @override
  void initState() {
    super.initState();
    vm = FormularioViewmodel(idFormulario: widget.idFormulario);

    WidgetsBinding.instance.addPostFrameCallback((_) {
      vm.loadFormulario();
    });
  }

  @override
  void dispose() {
    vm.dispose();
    super.dispose();
  }

  Widget _buildField(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 14,
              color: Colors.white54,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            value,
            style: const TextStyle(fontSize: 16, color: Colors.white),
            textAlign: TextAlign.justify,
          ),
        ],
      ),
    );
  }

  Future<void> _launchURL(String url) async {
    if (!url.startsWith('http://') && !url.startsWith('https://')) {
      url = 'https://$url';
    }

    final uri = Uri.parse(url);
    try {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    } catch (e) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Não foi possível abrir o link')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      appBar: SimpleAppBar(textAppBar: 'Formulário'),
      body: AnimatedBuilder(
        animation: vm,
        builder: (context, _) {
          if (vm.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (vm.formulario == null) {
            return const Center(
              child: Text(
                'Formulário não encontrado',
                style: TextStyle(color: Colors.white70, fontSize: 16),
              ),
            );
          }

          final f = vm.formulario!;
          return SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildField('Título', f.titulo),
                _buildField('Autor', f.nome),
                _buildField('Curso', f.curso),
                _buildField('Período', f.periodo),
                _buildField('Descrição', f.descricao),
                _buildField('Tipo formulário', f.tipo.descricao),
                _buildField('Data Limite', DateFormat('dd/MM/yyyy').format(f.dataLimite)),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () => _launchURL(f.linkForms),
                    icon: const Icon(Icons.open_in_browser),
                    label: const Text('Acessar formulário'),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
