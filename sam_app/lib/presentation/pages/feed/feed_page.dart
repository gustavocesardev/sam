import 'package:flutter/material.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_curso_page.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_geral_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/feed_app_bar.dart';

class FeedPage extends StatefulWidget {
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const FeedPage({
    super.key,
    required this.idAutor,
    required this.tipoAutorPublicacao
  });

  @override
  State<FeedPage> createState() => _FeedPageState();
}

class _FeedPageState extends State<FeedPage>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
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
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final List<Widget> tabsContent = [
      FeedGeralPage(idAutor: widget.idAutor, tipoAutorPublicacao: widget.tipoAutorPublicacao,),
      FeedCursoPage(idAutor: widget.idAutor, tipoAutorPublicacao: widget.tipoAutorPublicacao,)
    ];

    return Scaffold(
      appBar: FeedAppBar(tabController: _tabController),
      body: Center(child: tabsContent[_currentIndex]),
    );
  }
}
