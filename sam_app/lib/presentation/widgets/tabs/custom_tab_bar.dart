import 'package:flutter/material.dart';

class CustomTabBar extends StatelessWidget implements PreferredSizeWidget {
  final TabController tabController;
  final List<Tab> tabs;

  const CustomTabBar({
    super.key,
    required this.tabController,
    required this.tabs,
  });

  @override
  Widget build(BuildContext context) {
    return TabBar(
      controller: tabController,
      indicator: UnderlineTabIndicator(
        borderSide: BorderSide(
          width: 3.0,
          color: Theme.of(context).primaryColor,
          strokeAlign: 4,
        ),
      ),
      labelColor: Colors.white,
      unselectedLabelColor: Colors.white70,
      labelStyle: const TextStyle(fontWeight: FontWeight.bold),
      unselectedLabelStyle: const TextStyle(fontWeight: FontWeight.normal),
      dividerColor: Colors.transparent,
      overlayColor: WidgetStateProperty.all(Colors.transparent),
      tabs: tabs,
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}
