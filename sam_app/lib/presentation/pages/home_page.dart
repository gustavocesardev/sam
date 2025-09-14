import 'package:flutter/material.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/services/user_service.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';
import 'package:sam_app/presentation/pages/artigos/artigo_form_page.dart';
import 'package:sam_app/presentation/pages/artigos/artigos_page.dart';
import 'package:sam_app/presentation/pages/feed/feed_page.dart';
import 'package:sam_app/presentation/pages/formularios/formulario_form_page.dart';
import 'package:sam_app/presentation/pages/formularios/formularios_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupo_estudo_form_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_estudo_page.dart';
import 'package:sam_app/presentation/pages/publicacoes/post_create_page.dart';
import 'package:sam_app/presentation/vo/fab_config_vo.dart';
import 'package:sam_app/presentation/widgets/bottom_bar/custom_bottom_bar.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final UserService service = UserService();

  int _currentIndex = 0;
  late UserModel? userModel;

  @override
  void initState() {
    super.initState();
    _loadUser();
  }

  Future<void> _loadUser() async {
    final user = await AuthStorageService.getStoredUser();
    if (user != null) {
      final UserModel? currentUser = await service.getUser(user.id);

      if (!mounted) return;
      setState(() {
        userModel = currentUser!;
      });
    }
  }

  final List<Widget> _pages = [
    const FeedPage(),
    const GruposEstudoPage(),

    /// Apenas para ocupar o centro da BottomBar :P
    const FeedPage(),

    const FormulariosPage(),
    const ArtigosPage(),
  ];

  /// Definindo os Icons e suas rotas para a bottom bar
  Map<int, FabConfigVO> get _fabConfigs {
    return {
      0: FabConfigVO(
        icon: Icon(Icons.add, size: 30),
        builder: (_) => PostCreatePage(
          idAutor: userModel!.id,
          tipoAutor: TipoAutorPublicacao.usuario,
        ),
      ),
      1: FabConfigVO(
        icon: Icon(Icons.group_add, size: 30),
        builder: (_) => GrupoEstudoFormPage()
      ),
      3: FabConfigVO(
        icon: Icon(Icons.article_outlined, size: 30),
        builder: (_) => FormularioFormPage(idUsuario: userModel!.id,)
      ),
      4: FabConfigVO(
        icon: Icon(Icons.note_add_outlined, size: 30),
        builder: (_) => ArtigoFormPage()
      ),
    };
  }

  void _onFabPressed() {
  final config = _fabConfigs[_currentIndex];
    if (config != null) {
      if (config.route != null) {
        Navigator.pushNamed(context, config.route!);
      } else if (config.builder != null) {
        Navigator.push(
          context,
          MaterialPageRoute(builder: config.builder!),
        );
      }
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
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(32)),
        backgroundColor: Colors.blue[200],
        child: _getFabIcon(),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
    );
  }
}
