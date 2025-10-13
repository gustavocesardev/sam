import 'package:flutter/material.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupo_estudo_view_model.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_grupo_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupo_estudo_sobre_page.dart';
import 'package:sam_app/presentation/pages/publicacoes/post_create_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/header_app_bar.dart';
import 'package:sam_app/presentation/widgets/list_view/membro_list_view.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';

class GrupoEstudoPage extends StatefulWidget {
  final int idGrupoEstudo;
  final int idUsuario;
  final int? idMembro;

  const GrupoEstudoPage({
    super.key,
    required this.idGrupoEstudo,
    required this.idUsuario,
    this.idMembro,
  });

  @override
  State<GrupoEstudoPage> createState() => _GrupoEstudoPageState();
}

class _GrupoEstudoPageState extends State<GrupoEstudoPage>
    with SingleTickerProviderStateMixin {
  late GrupoEstudoViewModel vm;
  late TabController _tabController;
  int _currentIndex = 0;

  int? idMembro;

  @override
  void initState() {
    super.initState();
    idMembro = widget.idMembro;

    vm = GrupoEstudoViewModel(idGrupoEstudo: widget.idGrupoEstudo);
    vm.addListener(() {
      if (mounted) setState(() {});
    });
    vm.loadGrupo();

    _tabController = TabController(length: 2, vsync: this);
    _tabController.addListener(() {
      if (_tabController.indexIsChanging) {
        setState(() {
          _currentIndex = _tabController.index;
        });
      }
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    vm.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (vm.isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    final grupo = vm.grupo;

    final List<Widget> tabsContent = [
      idMembro != null
          ? FeedGrupoPage(
              idGrupoEstudo: widget.idGrupoEstudo,
              idAutor: idMembro!,
              tipoAutorPublicacao: TipoAutorPublicacao.membro,
            )
          : _buildJoinGroupPlaceholder(context),
      MembroListView(membros: vm.membros),
    ];

    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      floatingActionButton: idMembro != null
          ? Transform.translate(
              offset: const Offset(0, -20),
              child: FloatingActionButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => PostCreatePage(
                        idAutor: idMembro!,
                        tipoAutor: TipoAutorPublicacao.membro,
                      ),
                    ),
                  );
                },
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(32),
                ),
                backgroundColor: Colors.blue[200],
                child: const Icon(Icons.add, size: 30),
              ),
            )
          : null,
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
      body: Column(
        children: [
          HeaderAppBar(
            imageUrl: grupo.imagemHeaderUrl,
            onBackPressed: () => Navigator.pop(context),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(
              horizontal: 24.0,
              vertical: 4.0,
            ),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        grupo.nomeGrupo,
                        style: const TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        grupo.hashtags,
                        style: TextStyle(
                          color: Theme.of(context).colorScheme.secondary,
                        ),
                      ),
                    ],
                  ),
                ),
                IconButton(
                  onPressed: () async {
                    final saiu = await Navigator.push<bool>(
                      context,
                      MaterialPageRoute(
                        builder: (_) => GrupoEstudoSobrePage(
                          vm: vm,
                          idUsuario: widget.idUsuario,
                          idMembro: idMembro,
                        ),
                      ),
                    );

                    if (saiu == true) {
                      setState(() {
                        idMembro = null;
                      });
                    }
                  },
                  icon: const Icon(Icons.info_outline),
                  color: Colors.blue[200],
                  tooltip: 'Informações do grupo',
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          CustomTabBar(
            tabController: _tabController,
            tabs: const [
              Tab(text: 'Feed geral'),
              Tab(text: 'Membros'),
            ],
          ),
          const SizedBox(height: 16),
          Expanded(child: tabsContent[_currentIndex]),
        ],
      ),
    );
  }

  Widget _buildJoinGroupPlaceholder(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.group_add, size: 64, color: Colors.blue[200]),
          const SizedBox(height: 16),
          const Text(
            'Você ainda não é membro deste grupo',
            style: TextStyle(fontSize: 16, color: Colors.white70),
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            onPressed: () async {
              final novoIdMembro = await vm.ingressarGrupo(widget.idUsuario);

              setState(() {
                idMembro = novoIdMembro;
              });

              TopSnackBar.show(context, 'Você agora é membro do grupo!');
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Theme.of(context).primaryColor,
              padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 12),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
            icon: const Icon(Icons.login, color: Colors.white),
            label: const Text(
              'Ingressar no grupo',
              style: TextStyle(color: Colors.white),
            ),
          ),
        ],
      ),
    );
  }
}
