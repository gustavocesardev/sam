import 'package:flutter/material.dart';
import 'package:sam_app/presentation/widgets/app_bar/custom_app_bar.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';
import 'artigos_criados_page.dart';
import 'artigos_explorar_page.dart';

class ArtigosPage extends StatefulWidget {
  const ArtigosPage({super.key});

  @override
  State<ArtigosPage> createState() => _ArtigosPageState();
}

class _ArtigosPageState extends State<ArtigosPage>
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
  Widget build(BuildContext context) {
    final List<Widget> tabsContent = [
      ArtigosCriadosPage(),
      ArtigosExplorarPage(),
    ];

    return Scaffold(
      appBar: CustomAppBar(
        textAppBar: 'Artigos',
        customAppBar: CustomTabBar(
          tabController: _tabController,
          tabs: const [
            Tab(text: 'Meus artigos'),
            Tab(text: 'Explorar'),
          ],
        ),
      ),
      body: Center(child: tabsContent[_currentIndex]),
    );
  }
}
