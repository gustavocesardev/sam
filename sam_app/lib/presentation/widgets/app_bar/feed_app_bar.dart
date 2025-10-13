import 'package:flutter/material.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/services/user_service.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';
import 'package:sam_app/presentation/pages/profile_page.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';
import 'package:sam_app/shared/constants.dart';

class FeedAppBar extends StatefulWidget implements PreferredSizeWidget {
  final TabController tabController;

  const FeedAppBar({super.key, required this.tabController});

  @override
  Size get preferredSize => const Size.fromHeight(110);

  @override
  State<FeedAppBar> createState() => _FeedAppBarState();
}

class _FeedAppBarState extends State<FeedAppBar> {
  final UserService service = UserService();

  String? userImageUrl;
  int? userId;
  String? instituicaoImageUrl;

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
        userId = user.id;
        userImageUrl = currentUser?.avatarEncrypted != null
            ? "$baseUrl/file/image/${currentUser?.avatarEncrypted}"
            : null;
        instituicaoImageUrl = user.instituicao.imagemInstituicao.isNotEmpty
            ? "$baseUrl/file/image/${user.instituicao.imagemInstituicao}"
            : null;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return AppBar(
      surfaceTintColor: Theme.of(context).scaffoldBackgroundColor,
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      elevation: 0,
      title: Stack(
        children: [
          Align(
            alignment: Alignment.centerLeft,
            child: Material(
              color: Colors.transparent,
              child: InkWell(
                borderRadius: BorderRadius.circular(20),
                onTap: () {
                  if (userId != null) {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => ProfilePage(userId: userId!),
                      ),
                    ).then((_) {
                      _loadUser();
                    });
                  }
                },
                child: CircleAvatar(
                  backgroundColor: Theme.of(context).colorScheme.secondary,
                  radius: 18,
                  backgroundImage: userImageUrl != null
                      ? NetworkImage(userImageUrl!)
                      : null,
                  child: userImageUrl == null ? const Icon(Icons.person) : null,
                ),
              ),
            ),
          ),

          Align(
            alignment: Alignment.center,
            child: Row(
              mainAxisSize: MainAxisSize.min,
              spacing: 5,
              children: [
                const Text(
                  'SAM',
                  style: TextStyle(fontSize: 24, color: Colors.white),
                ),
                if (instituicaoImageUrl != null)
                  Text(
                    ' | ',
                    style: TextStyle(fontSize: 24, color: Colors.white),
                  ),
                if (instituicaoImageUrl != null)
                  ClipRRect(
                    borderRadius: BorderRadius.circular(10),
                    child: SizedBox(
                      height: 35,
                      width: 70,
                      child: Image.network(
                        instituicaoImageUrl!,
                        fit: BoxFit.contain,
                        alignment: Alignment.center,
                        errorBuilder: (_, __, ___) => Container(
                          color: Theme.of(context).scaffoldBackgroundColor,
                          child: Icon(
                            Icons.image_not_supported,
                            color: Theme.of(context).primaryColor,
                          ),
                        ),
                      ),
                    ),
                  ),
              ],
            ),
          ),
        ],
      ),
      bottom: CustomTabBar(
        tabController: widget.tabController,
        tabs: const [
          Tab(text: 'Feed principal'),
          Tab(text: 'Meu curso'),
        ],
      ),
    );
  }
}
