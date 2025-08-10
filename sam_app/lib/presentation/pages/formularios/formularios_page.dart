import 'package:flutter/material.dart';
import 'package:sam_app/presentation/pages/formularios/formularios_criados_page.dart';
import 'package:sam_app/presentation/pages/formularios/formularios_explorar_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/custom_app_bar.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';

class FormulariosPage extends StatefulWidget {
  const FormulariosPage({super.key});

  @override
  State<FormulariosPage> createState() => _FormulariosPageState();
}

class _FormulariosPageState extends State<FormulariosPage>
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
      FormulariosCriadosPage(),
      FormulariosExplorarPage(),
    ];

    return Scaffold(
      appBar: CustomAppBar(
        textAppBar: 'Formulários',
        customAppBar: CustomTabBar(
          tabController: _tabController,
          tabs: const [
            Tab(text: 'Meus forms'),
            Tab(text: 'Explorar'),
          ],
        ),
      ),
      body: Center(child: tabsContent[_currentIndex])
    );
  }
}
