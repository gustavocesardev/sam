import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart' as quill;
import 'package:sam_app/domain/viewmodels/artigo/artigo_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';

class ArtigoPage extends StatefulWidget {
  final int idArtigo;

  const ArtigoPage({super.key, required this.idArtigo});

  @override
  State<ArtigoPage> createState() => _ArtigoPageState();
}

class _ArtigoPageState extends State<ArtigoPage> {
  late final ArtigoViewmodel vm;

  @override
  void initState() {
    super.initState();
    vm = ArtigoViewmodel(idArtigo: widget.idArtigo);

    WidgetsBinding.instance.addPostFrameCallback((_) {
      vm.loadArtigo();
    });
  }

  @override
  void dispose() {
    vm.dispose();
    super.dispose();
  }

  Widget _buildField(
    String label,
    String value, {
    double valueFontSize = 16,
    Color valueColor = Colors.white,
    bool isBold = false,
  }) {
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
            style: TextStyle(
              fontSize: valueFontSize,
              color: valueColor,
              fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
            ),
            textAlign: TextAlign.justify,
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      appBar: SimpleAppBar(textAppBar: 'Artigo'),
      body: AnimatedBuilder(
        animation: vm,
        builder: (context, _) {
          if (vm.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (vm.artigo == null) {
            return const Center(
              child: Text(
                'Artigo não encontrado',
                style: TextStyle(color: Colors.white70, fontSize: 16),
              ),
            );
          }

          final a = vm.artigo!;
          return SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              spacing: 6,
              children: [
                _buildField(
                  'Título',
                  a.titulo,
                  valueFontSize: 18,
                  isBold: true,
                ),
                _buildField('Autor', a.nome),
                _buildField('Hashtags', a.palavrasChave),
                const SizedBox(height: 4),
                Text(
                  'Conteúdo',
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
                    color: Colors.white54,
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(vertical: 8),
                  child: quill.QuillEditor.basic(
                    controller: vm.conteudoController!,
                    config: quill.QuillEditorConfig(
                      scrollable: false,
                      autoFocus: false,
                      expands: false,
                      padding: EdgeInsets.zero,
                      showCursor: false,
                    ),
                    focusNode: FocusNode(),
                  ),
                ),
                const SizedBox(height: 38),
                if (a.pdf != null)
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: vm.isDownloadingPdf
                          ? null
                          : () async => await vm.downloadPdfWithLoading(),
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                      ),
                      child: vm.isDownloadingPdf
                          ? const SizedBox(
                              height: 20,
                              width: 20,
                              child: CircularProgressIndicator(
                                color: Colors.white,
                                strokeWidth: 2,
                              ),
                            )
                          : Row(
                              mainAxisSize: MainAxisSize.min,
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: const [
                                Icon(Icons.download),
                                SizedBox(width: 8),
                                Text('Baixar PDF'),
                              ],
                            ),
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
