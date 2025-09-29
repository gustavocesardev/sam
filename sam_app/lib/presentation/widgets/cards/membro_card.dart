import 'package:flutter/material.dart';
import 'package:sam_app/data/cache/image_cache_service.dart';
import 'package:sam_app/presentation/widgets/cached/cached_avatar.dart';
import 'package:sam_app/data/models/membro_model.dart';
import 'package:sam_app/shared/constants.dart';

class MembroCard extends StatelessWidget {
  final MembroModel membro;
  final Color avatarColor;
  final ImageCacheService _imageCacheService = ImageCacheService();

  MembroCard({super.key, required this.membro, required this.avatarColor});

  String imageUrlFromHash(String hash) => '$baseUrl/file/image/$hash';

  @override
  Widget build(BuildContext context) {
    return Card(
      color: Theme.of(context).scaffoldBackgroundColor,
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                CachedAvatar(
                  avatarHash: membro.fotoPerfilHash,
                  avatarColor: avatarColor,
                  imageUrlFromHash: imageUrlFromHash,
                  imageCacheService: _imageCacheService,
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        membro.nome,
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      Text(
                        membro.curso,
                        style: const TextStyle(
                          color: Colors.white60,
                          fontSize: 12,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          const Divider(color: Colors.white12, height: 1),
        ],
      ),
    );
  }
}
