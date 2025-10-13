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
import 'package:sam_app/domain/viewmodels/user_edit_viewmodel.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_curtidas_page.dart';
import 'package:sam_app/presentation/pages/feed/lists/feed_usuario_page.dart';
import 'package:sam_app/presentation/pages/user_edit_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
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
  String? biografia;
  String? criadoEm;
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

      final UserDetailModel? currentUser = await _userService.getUserDetails(
        widget.userId,
      );

      setState(() {
        name = currentUser?.nome;
        role = currentUser?.nomeCurso;
        biografia = currentUser?.biografia;
        criadoEm = currentUser?.criadoEm;
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

    Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
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
          create: (_) => FeedUsuarioViewmodel(FeedRepository(), widget.userId),
        ),
        ChangeNotifierProvider(
          create: (_) => FeedCurtidasViewmodel(FeedRepository(), widget.userId),
        ),
      ],
      child: Stack(
        children: [
          Scaffold(
            appBar: SimpleAppBar(
              textAppBar: 'Perfil',
              actions: [
                if (loggedUserId != null && loggedUserId == widget.userId)
                  Align(
                    alignment: Alignment.centerRight,
                    child: OutlinedButton(
                      style: OutlinedButton.styleFrom(
                        side: BorderSide(
                          color: Theme.of(context).colorScheme.primary,
                        ),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                        ),
                        padding: const EdgeInsets.symmetric(
                          horizontal: 16,
                          vertical: 1,
                        ),
                        backgroundColor: Theme.of(
                          context,
                        ).scaffoldBackgroundColor,
                      ),
                      onPressed: () async {
                        final updated = await Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => ChangeNotifierProvider(
                              create: (_) => UserEditViewmodel(),
                              child: UserEditPage(userId: widget.userId),
                            ),
                          ),
                        );
                        if (updated == true) _loadUserProfile();
                      },
                      child: Text(
                        'Editar perfil',
                        style: TextStyle(
                          color: Colors.white70,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                SizedBox(width: 15),
                if (loggedUserId != null && loggedUserId == widget.userId)
                  IconButton(
                    icon: const Icon(
                      Icons.logout,
                      color: Color.fromRGBO(211, 47, 47, 1),
                    ),
                    onPressed: _logout,
                  ),
              ],
            ),
            body: Column(
              children: [
                Padding(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          CircleAvatar(
                            radius: 50,
                            backgroundColor: Theme.of(
                              context,
                            ).colorScheme.secondary,
                            child: avatarUrl != null
                                ? ClipOval(
                                    child: Image.network(
                                      avatarUrl!,
                                      width: 100,
                                      height: 100,
                                      fit: BoxFit.cover,
                                      loadingBuilder: (context, child, loadingProgress) {
                                        if (loadingProgress == null) {
                                          return child;
                                        }
                                        return SizedBox(
                                          width: 100,
                                          height: 100,
                                          child: Center(
                                            child: CircularProgressIndicator(
                                              value:
                                                  loadingProgress
                                                          .expectedTotalBytes !=
                                                      null
                                                  ? loadingProgress
                                                            .cumulativeBytesLoaded /
                                                        loadingProgress
                                                            .expectedTotalBytes!
                                                  : null,
                                            ),
                                          ),
                                        );
                                      },
                                      errorBuilder:
                                          (context, error, stackTrace) {
                                            return Icon(
                                              Icons.person,
                                              size: 60,
                                              color: Theme.of(
                                                context,
                                              ).scaffoldBackgroundColor,
                                            );
                                          },
                                    ),
                                  )
                                : Icon(
                                    Icons.person,
                                    size: 60,
                                    color: Theme.of(
                                      context,
                                    ).scaffoldBackgroundColor,
                                  ),
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
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    _buildStat('Publicações', postsCount ?? 0),
                                    _buildStat('Artigos', articlesCount ?? 0),
                                    _buildStat(
                                      'Comentários',
                                      commentsCount ?? 0,
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      if (biografia != null && biografia!.isNotEmpty)
                        Padding(
                          padding: const EdgeInsets.only(top: 24.0),
                          child: Text(
                            biografia!,
                            textAlign: TextAlign.justify,
                            style: TextStyle(color: Colors.white, fontSize: 14),
                          ),
                        ),
                      if (criadoEm != null)
                        Padding(
                          padding: const EdgeInsets.only(top: 24.0),
                          child: Row(
                            children: [
                              Icon(
                                Icons.calendar_today,
                                size: 16,
                                color: Colors.grey,
                              ),
                              const SizedBox(width: 6),
                              Text(
                                'Entrou $criadoEm',
                                style: TextStyle(
                                  color: Colors.grey,
                                  fontSize: 12,
                                ),
                              ),
                            ],
                          ),
                        ),
                    ],
                  ),
                ),
                if (criadoEm == null) const SizedBox(height: 5),
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
