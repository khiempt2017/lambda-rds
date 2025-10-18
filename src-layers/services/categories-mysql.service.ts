/**
 * categories-mysql.service.ts
 * BP-api-serverless
 * Service layer for Categories MySQL operations
 * Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { Categories, CategoriesMySQLModel, CategoriesData } from '../database/models/categories-mysql.model';
import { BaseMySQLService } from './base-mysql.service';
import { ResultSetHeader } from 'mysql2/promise';

export class CategoriesMySQLService extends BaseMySQLService<Categories> {
  private categoriesModel: CategoriesMySQLModel;

  constructor(categoriesModel: CategoriesMySQLModel = new CategoriesMySQLModel()) {
    super(categoriesModel);
    this.categoriesModel = categoriesModel;
  }

  /**
   * Find category by name
   * @param name - The name of the category
   * @returns Category record or null
   */
  public async findCategoryByName(name: string): Promise<Categories | null> {
    const category = await this.categoriesModel.findByName(name);
    return category || null;
  }

  /**
   * Find active categories
   * @returns Array of active categories
   */
  public async findActiveCategories(): Promise<Categories[]> {
    return await this.findBy({ status: 1 });
  }

  /**
   * Create a new category
   * @param data - Category data
   * @returns Insert result
   */
  public async createCategory(data: Omit<CategoriesData, 'id'>): Promise<ResultSetHeader> {
    return await this.categoriesModel.createCategory(data);
  }

  /**
   * Update category by ID
   * @param id - Category ID
   * @param data - Data to update
   * @returns Update result
   */
  public async updateCategory(id: number, data: Partial<Omit<CategoriesData, 'id'>>): Promise<ResultSetHeader> {
    return await this.categoriesModel.updateCategory(id, data);
  }

  /**
   * Delete category by ID
   * @param id - Category ID
   * @returns Delete result
   */
  public async deleteCategory(id: number): Promise<ResultSetHeader> {
    // Check if category can be deleted (no products associated)
    const canDelete = await this.categoriesModel.canDeleteCategory(id);
    if (!canDelete) {
      throw new Error('Cannot delete category: there are products associated with this category');
    }
    return await this.categoriesModel.deleteCategory(id);
  }

  /**
   * Get category statistics
   * @returns Statistics object
   */
  public async getCategoryStatistics(): Promise<any> {
    return await this.categoriesModel.getStatistics();
  }

  /**
   * Check if category can be deleted
   * @param id - Category ID
   * @returns True if can be deleted, false otherwise
   */
  public async canDeleteCategory(id: number): Promise<boolean> {
    return await this.categoriesModel.canDeleteCategory(id);
  }
}
