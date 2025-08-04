import 'package:flutter/material.dart';
import 'package:sam_app/data/cache/image_cache_service.dart';
import 'package:sam_app/presentation/widgets/cached/cached_avatar.dart';
import 'package:sam_app/shared/constants.dart';

class GrupoCard extends StatelessWidget {
  final String iconPath;
  final String nome;
  final int membros;
  final String tags;
  final String? route;

  const GrupoCard({
    super.key,
    required this.iconPath,
    required this.nome,
    required this.membros,
    required this.tags,
    this.route,
  });

  String imageUrlFromHash(String hash) => '$baseUrl/file/image/$hash';

  @override
  Widget build(BuildContext context) {
    final ImageCacheService _imageCacheService = ImageCacheService();

    return InkWell(
      onTap: route != null
          ? () {
              Navigator.pushNamed(context, route!);
            }
          : null,
      borderRadius: BorderRadius.circular(12),
      child: Card(
        color: Theme.of(context).scaffoldBackgroundColor,
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            children: [
              Row(
                children: [
                  CachedAvatar(
                    avatarHash: iconPath,
                    avatarColor: Theme.of(context).colorScheme.secondary,
                    circleRadius: 44,
                    imageSize: 88,
                    iconSize: 52,
                    iconNotFound: Icons.groups_rounded,
                    iconColor: Theme.of(context).scaffoldBackgroundColor,
                    imageUrlFromHash: imageUrlFromHash,
                    imageCacheService: _imageCacheService,
                  ),
                  const SizedBox(width: 24),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          nome,
                          style: const TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Text(
                          "$membros membros",
                          style: const TextStyle(
                            fontSize: 12,
                            color: Colors.white70,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Wrap(
                          spacing: 8,
                          children: [
                            Text(
                              tags,
                              style: TextStyle(
                                fontSize: 12,
                                color: Theme.of(context).colorScheme.secondary,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Divider(color: Colors.white12, height: 1),
            ],
          ),
        ),
      ),
    );
  }
}
