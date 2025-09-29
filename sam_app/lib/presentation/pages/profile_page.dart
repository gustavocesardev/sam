import 'package:flutter/material.dart';
import 'package:sam_app/data/models/post_model.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/services/user_service.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';
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

  bool isLoading = true;
  String? name;
  String? role;
  String? avatarUrl;
  int? postsCount;
  int? articlesCount;
  int? commentsCount;

  List<PostModel> posts = [];
  List<PostModel> likedPosts = [];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _loadUserProfile();
  }

  Future<void> _loadUserProfile() async {
    setState(() => isLoading = true);

    try {
      // pega user logado do storage
      final storedUser = await AuthStorageService.getStoredUser();
      if (storedUser != null) {
        // consulta backend para dados mais atuais
        final UserModel? currentUser = await _userService.getUser(
          widget.userId,
        );

        setState(() {
          name = currentUser?.nome;
          role =
              "${storedUser.curso.periodo}° ${storedUser.curso.nomeCurso}";
          avatarUrl = "$baseUrl/file/image/${currentUser!.avatarEncrypted}";
          postsCount = 5;
          articlesCount = 17;
          commentsCount = 3;

          // simulação de posts
          posts = [
            PostModel(
              id: 1,
              nome: name!,
              curso: role!,
              texto: 'A palestra de segurança da informação foi ótima!',
              imagens: [],
              criadoEm: '2025-08-31',
              curtidas: 42,
              comentarios: 5,
              avatarEncrypted: currentUser.avatarEncrypted,
              curtido: false
            ),
            PostModel(
              id: 1,
              nome: name!,
              curso: role!,
              texto:
                  'Os estudos de DDD contribuiram muito para minha visão de arquitetura de software!',
              imagens: [],
              criadoEm: '2025-08-31',
              curtidas: 42,
              comentarios: 5,
              avatarEncrypted: currentUser.avatarEncrypted,
              curtido: false
            ),
            PostModel(
              id: 1,
              nome: name!,
              curso: role!,
              texto: 'Será que essa boom! da IA não é apenas questão de bolha?',
              imagens: [],
              criadoEm: '2025-08-31',
              curtidas: 42,
              comentarios: 10,
              avatarEncrypted: currentUser.avatarEncrypted,
              curtido: false
            ),
            PostModel(
              id: 1,
              nome: name!,
              curso: role!,
              texto:
                  'A palestra de segurança de análise de dados foi ótima mas poderia ter tratado conteúdos mais recentes!',
              imagens: [],
              criadoEm: '2025-08-31',
              curtidas: 42,
              comentarios: 99,
              avatarEncrypted: currentUser.avatarEncrypted,
              curtido: false
            ),
          ];

          likedPosts = List.from(posts);
        });
      }
    } finally {
      setState(() => isLoading = false);
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: SimpleAppBar(textAppBar: 'Perfil'),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: 5),

                  /// Cabeçalho com Avatar + Infos
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 20),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        CircleAvatar(
                          radius: 50,
                          backgroundImage: avatarUrl != null
                              ? NetworkImage(avatarUrl!)
                              : null,
                          backgroundColor: Colors.grey[600],
                          child: avatarUrl == null
                              ? const Icon(Icons.person, size: 40)
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
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                role ?? '',
                                style: const TextStyle(
                                  color: Colors.grey,
                                  fontSize: 13,
                                ),
                              ),
                              const SizedBox(height: 20),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
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

                  const SizedBox(height: 30),

                  /// Tabs
                  Column(
                    children: [
                      CustomTabBar(
                        tabController: _tabController,
                        tabs: const [
                          Tab(text: 'Publicações'),
                          Tab(text: 'Curtidas'),
                        ],
                      ),
                      SizedBox(
                        height: 600,
                        child: TabBarView(
                          controller: _tabController,
                          children: [
                          ],
                        ),
                      ),
                    ],
                  ),
                ],
              ),
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
