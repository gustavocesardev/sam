import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/models/post_model.dart';
import 'package:sam_app/data/models/user_detail_model.dart';
import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/data/services/user_service.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_curtidas_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_usuario_viewmodel.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_curtidas_page.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_usuario_page.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';
import 'package:sam_app/shared/constants.dart';

class ProfilePage extends StatefulWidget {
  final int userId;

  const ProfilePage({super.key, required this.userId});

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final UserService _userService = UserService();

  int _currentIndex = 0;
  bool isLoading = true;
  bool _isLoggingOut = false;

  String? name;
  String? role;
  String? avatarUrl;
  int? postsCount;
  int? articlesCount;
  int? commentsCount;
  int? loggedUserId;

  List<PostModel> posts = [];
  List<PostModel> likedPosts = [];

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
    _loadUserProfile();
    _loadLoggedUser();
  }

  Future<void> _loadUserProfile() async {
    setState(() => isLoading = true);
    try {
      final UserDetailModel? currentUser =
          await _userService.getUserDetails(widget.userId);

      setState(() {
        name = currentUser?.nome;
        role = currentUser?.nomeCurso;
        avatarUrl = currentUser!.avatarEncrypted != null
            ? "$baseUrl/file/image/${currentUser.avatarEncrypted}"
            : null;
        postsCount = currentUser.totalPublicacoes;
        articlesCount = currentUser.totalArtigos;
        commentsCount = currentUser.totalComentarios;
      });
    } finally {
      setState(() => isLoading = false);
    }
  }

  Future<void> _loadLoggedUser() async {
    final user = await AuthStorageService.getStoredUser();
    if (mounted) {
      setState(() => loggedUserId = user?.id);
    }
  }

  Future<void> _logout() async {
    setState(() => _isLoggingOut = true);

    final storage = await AuthStorageService.init();
    storage.clear();

    await Future.delayed(const Duration(milliseconds: 300));

    if (!mounted) return;

    Navigator.pushNamedAndRemoveUntil(
      context,
      '/login', // ou AppRoutes.login
      (route) => false,
    );
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final List<Widget> tabsContent = [
      FeedUsuarioPage(
        idAutor: widget.userId,
        tipoAutorPublicacao: TipoAutorPublicacao.usuario,
      ),
      FeedCurtidasPage(
        idAutor: widget.userId,
        tipoAutorPublicacao: TipoAutorPublicacao.usuario,
      ),
    ];

    return MultiProvider(
      providers: [
        ChangeNotifierProvider(
          create: (_) => FeedUsuarioViewmodel(FeedRepository()),
        ),
        ChangeNotifierProvider(
          create: (_) => FeedCurtidasViewmodel(FeedRepository()),
        ),
      ],
      child: Stack(
        children: [
          Scaffold(
            appBar: AppBar(
              title: Text('Perfil'),
              actions: [
                if (loggedUserId != null && loggedUserId == widget.userId)
                  IconButton(
                    icon: const Icon(Icons.logout, color: Color.fromRGBO(211, 47, 47, 1),),
                    onPressed: _logout,
                  ),
              ],
            ),
            body: Column(
              children: [
                Padding(
                  padding: const EdgeInsets.all(20),
                  child: Row(
                    children: [
                      CircleAvatar(
                        radius: 50,
                        backgroundColor: Theme.of(context).colorScheme.secondary,
                        backgroundImage: avatarUrl != null
                            ? NetworkImage(avatarUrl!)
                            : null,
                        child: avatarUrl == null
                            ? Icon(
                                Icons.person,
                                size: 60,
                                color:
                                    Theme.of(context).scaffoldBackgroundColor,
                              )
                            : null,
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              name ?? '',
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            Text(
                              role ?? '',
                              style: const TextStyle(color: Colors.grey),
                            ),
                            const SizedBox(height: 18),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                _buildStat('Publicações', postsCount ?? 0),
                                _buildStat('Artigos', articlesCount ?? 0),
                                _buildStat('Comentários', commentsCount ?? 0),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),
                CustomTabBar(
                  tabController: _tabController,
                  tabs: const [
                    Tab(text: 'Publicações'),
                    Tab(text: 'Curtidas'),
                  ],
                ),
                const SizedBox(height: 16),
                Expanded(child: tabsContent[_currentIndex]),
              ],
            ),
          ),

          if (isLoading || _isLoggingOut)
            Positioned.fill(
              child: Container(
                color: Theme.of(context).scaffoldBackgroundColor,
                child: const Center(child: CircularProgressIndicator()),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildStat(String label, int value) {
    return Column(
      children: [
        Text(
          value.toString(),
          style: TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 12,
            color: Theme.of(context).colorScheme.secondary,
          ),
        ),
        const SizedBox(height: 4),
        Text(label, style: const TextStyle(color: Colors.white, fontSize: 10)),
      ],
    );
  }
}
