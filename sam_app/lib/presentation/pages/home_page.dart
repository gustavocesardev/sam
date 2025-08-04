import 'package:flutter/material.dart';
import 'package:sam_app/presentation/pages/artigos/artigos_page.dart';
import 'package:sam_app/presentation/pages/feed/feed_page.dart';
import 'package:sam_app/presentation/pages/forms/formularios_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_estudo_page.dart';
import 'package:sam_app/presentation/vo/fab_config_vo.dart';
import 'package:sam_app/presentation/widgets/bottom_bar/custom_bottom_bar.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const FeedPage(),
    const GruposEstudoPage(),

    const FeedPage(), /// Apenas para ocupar o centro da BottomBar :P

    const FormulariosPage(),
    const ArtigosPage(),
  ];

  /// Definindo os Icons e suas rotas para a bottom bar
  final Map<int, FabConfigVO> _fabConfigs = {
    0: FabConfigVO(
      icon: Icon(Icons.add, size: 30),
      route: '/'
    ),

    1: FabConfigVO(
      icon: Icon(Icons.group_add, size: 30),
      route: '/'
    ),

    3: FabConfigVO(
      icon: Icon(Icons.article_outlined, size: 30),
      route: '/'
    ),

    4: FabConfigVO(
      icon: Icon(Icons.note_add_outlined, size: 30),
      route: '/'
    ),
  };

  void _onFabPressed() {
    final config = _fabConfigs[_currentIndex];
    if (config != null) {
      Navigator.pushNamed(context, config.route);
    }
  }

  Icon _getFabIcon() {
    return _fabConfigs[_currentIndex]?.icon ?? const Icon(Icons.add, size: 30);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      body: _pages[_currentIndex],
      bottomNavigationBar: CustomBottomBar(
        currentIndex: _currentIndex,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _onFabPressed,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(32),
        ),
        backgroundColor: Colors.blue[200],
        child: _getFabIcon(),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
    );
  }
}