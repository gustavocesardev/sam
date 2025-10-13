import 'package:flutter/material.dart';
import 'package:palette_generator/palette_generator.dart';
import 'package:sam_app/data/models/auth/instituicao_model.dart';
import 'package:sam_app/data/repositories/instituicao_repository.dart';
import 'package:sam_app/presentation/pages/register_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/shared/constants.dart';

class InstituicoesPage extends StatefulWidget {
  const InstituicoesPage({super.key});

  @override
  State<InstituicoesPage> createState() => _InstituicoesPageState();
}

class _InstituicoesPageState extends State<InstituicoesPage> {
  final InstituicaoRepository _repository = InstituicaoRepository();
  bool _loading = true;
  List<InstituicaoModel> _instituicoes = [];
  final Map<int, Color> _instituicaoColors = {};

  @override
  void initState() {
    super.initState();
    _fetchInstituicoes();
  }

  Future<void> _fetchInstituicoes() async {
    try {

      final result = await _repository.getInstituicoes();
      _instituicoes = result.cast<InstituicaoModel>();

      /// Extrair cores predominantes
      for (var inst in _instituicoes) {
        final PaletteGenerator paletteGenerator =
            await PaletteGenerator.fromImageProvider(
              NetworkImage('$baseUrl/file/image/${inst.imagemInstituicao}'),
            );

        /// Usa a cor mais vibrante ou dominante
        _instituicaoColors[inst.idInstituicao] =
            paletteGenerator.dominantColor?.color ?? Colors.blue[900]!;
      }

      setState(() {
        _loading = false;
      });

    } catch (e) {
      setState(() => _loading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Erro ao carregar instituições ${e.toString()}'),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: SimpleAppBar(textAppBar: ''),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: SingleChildScrollView(
                child: Column(
                  children: [
                    const SizedBox(height: 75),
                    const Column(
                      children: [
                        Text('SAM', style: TextStyle(fontSize: 80)),
                        Text('Social Academic Media'),
                      ],
                    ),
                    const SizedBox(height: 62),
                    const Text(
                      'Selecione sua instituição',
                      style: TextStyle(fontSize: 24),
                    ),
                    const SizedBox(height: 16),
                    ListView.builder(
                      shrinkWrap: true,
                      itemCount: _instituicoes.length,
                      itemBuilder: (_, index) {
                        final inst = _instituicoes[index];
                        return Padding(
                          padding: const EdgeInsets.symmetric(vertical: 8),
                          child: GestureDetector(
                            onTap: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => RegisterPage(
                                    instituicaoId: inst.idInstituicao,
                                    dominioInstituicao: inst.dominioInstituicao,
                                    nomeInstituicao: inst.nomeInstituicao,
                                  ),
                                ),
                              );
                            },
                            child: Card(
                              color: _instituicaoColors[inst.idInstituicao],
                              elevation: 6,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(16),
                              ),
                              child: Padding(
                                padding: const EdgeInsets.all(16.0),
                                child: Row(
                                  children: [
                                    ClipRRect(
                                      borderRadius: BorderRadius.circular(10),
                                      child: SizedBox(
                                        height: 60,
                                        width: 60,
                                        child: Image.network(
                                          '$baseUrl/file/image/${inst.imagemInstituicao}',
                                          fit: BoxFit.contain,
                                          alignment: Alignment.center,
                                          errorBuilder: (_, __, ___) =>
                                              Container(
                                                color: Colors.grey[700],
                                                child: const Icon(
                                                  Icons.image_not_supported,
                                                  color: Colors.white54,
                                                ),
                                              ),
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 16),
                                    Expanded(
                                      child: Text(
                                        inst.nomeInstituicao.toUpperCase(),
                                        style: const TextStyle(
                                          fontSize: 14,
                                          fontWeight: FontWeight.bold,
                                          color: Colors.white,
                                        ),
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        );
                      },
                    ),
                  ],
                ),
              ),
            ),
    );
  }
}
