import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart' as quill;
import 'package:sam_app/domain/viewmodels/artigo/artigo_form_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/presentation/widgets/input/custom_text_form_field.dart';
import 'package:sam_app/presentation/widgets/buttons/custom_icon_button.dart';
import 'package:sam_app/presentation/widgets/buttons/loading_button.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class ArtigoFormPage extends StatefulWidget {
  final int idUsuario;
  final int? idArtigo;

  const ArtigoFormPage({super.key, this.idArtigo, required this.idUsuario});

  @override
  State<ArtigoFormPage> createState() => _ArtigoFormPageState();
}

class _ArtigoFormPageState extends State<ArtigoFormPage> {
  late final ArtigoFormViewModel vm;
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    vm = ArtigoFormViewModel(
      idUsuario: widget.idUsuario,
      idArtigo: widget.idArtigo,
    );
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
      body: AnimatedBuilder(
        animation: vm,
        builder: (context, _) {
          if (vm.isLoading) {
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
                          controller: vm.hashtagsController,
                          label: 'Hashtags*',
                          hint: 'Ex: #dev #adm',
                        ),
                        const SizedBox(height: 28),
                        Text('Conteúdo*', style: TextStyle(color: Colors.grey)),
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
                                  color: Theme.of(
                                    context,
                                  ).colorScheme.secondary,
                                ),
                                const SizedBox(width: 10),
                                Expanded(
                                  child: Text(
                                    vm.pdfSelecionado != null
                                        ? vm.pdfSelecionado!.path
                                              .split('/')
                                              .last
                                        : 'Selecione seu novo artigo em PDF',
                                    style: TextStyle(color: Colors.white70),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                    vm.pdfUrl != null
                      ? Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            const SizedBox(height: 55),
                            TextButton.icon(
                              icon: Icon(Icons.download),
                              label: Text('Baixar PDF atual'),
                              onPressed: () {
                                vm.service.downloadPdf(
                                  vm.pdfUrl!,
                                  vm.tituloController.text,
                                );
                              },
                            ),
                          ],
                        )
                      : const SizedBox(height: 50),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        widget.idArtigo != null
                            ? vm.isLoadingDelete
                                  ? const LoadingButtonSimple()
                                  : CustomIconButton(
                                      label: "Excluir",
                                      color: Colors.red.shade700,
                                      icon: Icons.close,
                                      onPressed: () async {
                                        try {
                                          await vm.excluirArtigo();
                                          if (context.mounted) {
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
                        vm.isSaving
                            ? const LoadingButtonSimple()
                            : CustomIconButton(
                                label: widget.idArtigo == null
                                    ? "Publicar"
                                    : "Atualizar",
                                color: Theme.of(context).colorScheme.primary,
                                icon: Icons.send,
                                onPressed: () async {
                                  if (_formKey.currentState!.validate()) {
                                    try {
                                      vm.salvarArtigo();

                                      if (context.mounted) {
                                        TopSnackBar.show(
                                          context,
                                          'Artigo gravado com sucesso!',
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
