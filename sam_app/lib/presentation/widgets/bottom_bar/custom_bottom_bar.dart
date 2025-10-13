import 'package:flutter/material.dart';

class CustomBottomBar extends StatelessWidget {
  final int currentIndex;
  final Function(int) onTap;

  const CustomBottomBar({
    super.key,
    required this.currentIndex,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return NavigationBar(
      backgroundColor: theme.scaffoldBackgroundColor,
      selectedIndex: currentIndex,
      onDestinationSelected: onTap,
      indicatorColor: theme.colorScheme.primary,
      height: 70,
      destinations: [
        NavigationDestination(
          icon: Icon(Icons.home_outlined),
          selectedIcon: Icon(Icons.home, color: Colors.white),
          label: 'Início',
        ),
        NavigationDestination(
          icon: Icon(Icons.group_outlined),
          selectedIcon: Icon(Icons.group, color: Colors.white),
          label: 'Grupos',
        ),
        SizedBox(width: 25),
        NavigationDestination(
          icon: Icon(Icons.article_outlined),
          selectedIcon: Icon(Icons.article, color: Colors.white),
          label: 'Formulários',
        ),
        NavigationDestination(
          icon: Icon(Icons.edit_document),
          selectedIcon: Icon(Icons.edit_document, color: Colors.white),
          label: 'Artigos',
        ),
      ],
    );
  }
}
