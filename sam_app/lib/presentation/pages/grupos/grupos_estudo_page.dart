import 'package:flutter/material.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_criados_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_ingressados_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_populares_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/custom_app_bar.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';

class GruposEstudoPage extends StatefulWidget {
  const GruposEstudoPage({super.key});

  @override
  State<GruposEstudoPage> createState() => _GruposEstudoPageState();
}

class _GruposEstudoPageState extends State<GruposEstudoPage>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);

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
      GruposCriadosPage(),
      GruposIngressadosPage(),
      GruposPopularesPage(),
    ];

    return Scaffold(
      appBar: CustomAppBar(
        textAppBar: 'Grupos de estudo',
        customAppBar: CustomTabBar(
          tabController: _tabController,
          tabs: const [
            Tab(text: 'Meus grupos'),
            Tab(text: 'Ingressados'),
            Tab(text: 'Explorar'),
          ],
        ),
      ),
      body: Center(child: tabsContent[_currentIndex]),
    );
  }
}
