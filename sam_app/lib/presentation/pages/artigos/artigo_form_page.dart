import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart' as quill;
import 'package:sam_app/domain/viewmodels/artigo/artigo_form_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/presentation/widgets/input/custom_text_form_field.dart';

class ArtigoFormPage extends StatefulWidget {
  final int? idArtigo;

  const ArtigoFormPage({super.key, this.idArtigo});

  @override
  State<ArtigoFormPage> createState() => _ArtigoFormPageState();
}

class _ArtigoFormPageState extends State<ArtigoFormPage> {
  late final ArtigoFormViewModel vm;
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    vm = ArtigoFormViewModel(idArtigo: widget.idArtigo);
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
        textAppBar: widget.idArtigo == null ? 'Novo artigo' : 'Editar artigo',
      ),
      body: vm.isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              child: Padding(
                padding: const EdgeInsets.only(
                  top: 20,
                  bottom: 10,
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
                          const SizedBox(height: 20),
                          CustomTextFormField(
                            controller: vm.hashtagsController,
                            label: 'Hashtags*',
                            hint: 'Ex: #dev #adm',
                          ),
                          const SizedBox(height: 20),
                          Text(
                            'Conteúdo*',
                            style: TextStyle(color: Colors.grey),
                          ),
                          const SizedBox(height: 8),
                          Container(
                            decoration: BoxDecoration(
                              border: Border.all(
                                color: Colors.white24,
                                width: 0.8,
                              ),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Column(
                              children: [
                                quill.QuillSimpleToolbar(
                                  controller: vm.conteudoController,
                                  config: const quill.QuillSimpleToolbarConfig(
                                    toolbarSectionSpacing: 4,
                                    showAlignmentButtons: false,
                                    showBackgroundColorButton: false,
                                    showBoldButton: true,
                                    showIndent: false,
                                    showItalicButton: true,
                                    showUnderLineButton: false,
                                    showStrikeThrough: false,
                                    showCodeBlock: false,
                                    showListCheck: false,
                                    showListBullets: true,
                                    showListNumbers: true,
                                    showQuote: false,
                                    showInlineCode: false,
                                    showFontFamily: false,
                                    showFontSize: false,
                                    showLink: false,
                                    showUndo: false,
                                    showRedo: false,
                                    showColorButton: false,
                                    showSearchButton: false,
                                    showSubscript: false,
                                    showSuperscript: false,
                                    showHeaderStyle: false,
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  constraints: const BoxConstraints(
                                    minHeight: 325,
                                  ),
                                  padding: const EdgeInsets.all(8),
                                  child: quill.QuillEditor.basic(
                                    config: const quill.QuillEditorConfig(
                                      checkBoxReadOnly: false,
                                      padding: EdgeInsets.zero,
                                    ),
                                    controller: vm.conteudoController,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(height: 28),
                          GestureDetector(
                            onTap: vm.selecionarPDF,
                            child: Container(
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                border: Border.all(
                                  color: Colors.grey.shade700,
                                  width: 0.75,
                                ),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Row(
                                children: [
                                  Icon(
                                    Icons.upload_file,
                                    size: 28,
                                    color: Theme.of(context).colorScheme.secondary,
                                  ),
                                  const SizedBox(width: 10),
                                  Expanded(
                                    child: Text(
                                      vm.pdfSelecionado != null
                                          ? vm.pdfSelecionado!.path.split('/').last
                                          : 'Selecione seu artigo em PDF',
                                      style: TextStyle(color: Colors.white70),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 50),
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
                            label: "Publicar",
                            color: Theme.of(context).colorScheme.primary,
                            icon: Icons.send,
                            onPressed: () {
                              if (_formKey.currentState!.validate()) {
                                vm.salvarArtigo();
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