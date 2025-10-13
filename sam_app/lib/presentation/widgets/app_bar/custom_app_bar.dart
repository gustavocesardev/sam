import 'package:flutter/material.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/services/user_service.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';
import 'package:sam_app/presentation/widgets/tabs/custom_tab_bar.dart';
import 'package:sam_app/shared/constants.dart';

class CustomAppBar extends StatefulWidget implements PreferredSizeWidget {
  final String textAppBar;
  final CustomTabBar customAppBar;

  const CustomAppBar({
    super.key,
    required this.textAppBar,
    required this.customAppBar,
  });

  @override
  Size get preferredSize => const Size.fromHeight(110);

  @override
  State<CustomAppBar> createState() => _CustomAppBarState();
}

class _CustomAppBarState extends State<CustomAppBar> {
  final UserService service = UserService();

  String? userImageUrl;

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
        userImageUrl = currentUser?.avatarEncrypted != null
            ? "$baseUrl/file/image/${currentUser?.avatarEncrypted}"
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
            child: CircleAvatar(
              radius: 18,
              backgroundColor: Theme.of(context).colorScheme.secondary,
              backgroundImage: userImageUrl != null
                  ? NetworkImage(userImageUrl!)
                  : null,
              child: userImageUrl == null
                  ? Icon(
                      Icons.person,
                      color: Theme.of(context).scaffoldBackgroundColor,
                    )
                  : null,
            ),
          ),
          Align(
            alignment: Alignment.center,
            child: Row(
              mainAxisSize: MainAxisSize.min,
              spacing: 5,
              children: [
                Text(
                  widget.textAppBar,
                  style: TextStyle(fontSize: 24, color: Colors.white),
                ),
              ],
            ),
          ),
        ],
      ),
      bottom: widget.customAppBar,
    );
  }
}
